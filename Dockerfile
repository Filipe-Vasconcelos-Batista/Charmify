FROM php:8.4-apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Install system dependencies
RUN apt-get update && apt-get -y install \
    libzip-dev \
    libicu-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip mysqli intl
RUN docker-php-ext-enable pdo pdo_mysql zip mysqli intl

# Configure Apache document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html
COPY my-httpd.conf /etc/apache2/sites-available/

# Set working directory
WORKDIR /var/www/html

# Set environment for development
ENV APP_ENV=dev
ENV APP_DEBUG=1

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create entrypoint script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Configuring git safe directory..."\n\
git config --global --add safe.directory /var/www/html\n\
\n\
echo "Installing Composer dependencies..."\n\
if [ "$APP_ENV" = "prod" ]; then\n\
  composer install --no-dev --optimize-autoloader --ignore-platform-req=php\n\
else\n\
  composer install --optimize-autoloader --ignore-platform-req=php\n\
fi\n\
\n\
echo "Waiting for database to be ready..."\n\
until php bin/console dbal:run-sql "SELECT 1" > /dev/null 2>&1; do\n\
  echo "Waiting for database connection..."\n\
  sleep 2\n\
done\n\
\n\
echo "Running database migrations..."\n\
if php bin/console doctrine:migrations:status --no-interaction > /dev/null 2>&1; then\n\
  php bin/console doctrine:migrations:migrate --no-interaction || echo "Migration failed - continuing..."\n\
else\n\
  echo "No migrations configured - skipping..."\n\
fi\n\
\n\
echo "Setting up messenger transports..."\n\
php bin/console messenger:setup-transports --no-interaction || echo "Messenger setup failed - continuing..."\n\
\n\
echo "Starting services..."\n\
echo "Starting messenger consumer in background..."\n\
nohup php bin/console messenger:consume async > /var/log/messenger.log 2>&1 &\n\
MESSENGER_PID=$!\n\
echo "Messenger consumer started with PID: $MESSENGER_PID"\n\
\n\
echo "Starting Apache..."\n\
if ! apache2ctl configtest; then\n\
  echo "Apache configuration test failed - fixing and continuing..."\n\
  echo "ServerName localhost" >> /etc/apache2/apache2.conf\n\
fi\n\
\n\
# Create a function to handle graceful shutdown\n\
cleanup() {\n\
  echo "Shutting down services..."\n\
  if kill -0 $MESSENGER_PID 2>/dev/null; then\n\
    kill $MESSENGER_PID\n\
  fi\n\
  apache2ctl graceful-stop\n\
  exit 0\n\
}\n\
\n\
trap cleanup SIGTERM SIGINT\n\
\n\
exec apache2-foreground\n\
' > /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]
