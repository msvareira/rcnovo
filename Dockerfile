# Use a imagem oficial do Ubuntu 20.04 como base
FROM ubuntu:20.04

# Atualize os repositórios e instale o cURL e o Node.js
RUN apt-get update && apt-get install -y curl gnupg2
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Atualize os repositórios e instale o cURL
RUN apt-get update && apt-get install -y curl

# Adicione o repositório Ondřej Surý para obter o PHP 8.3
RUN apt-get install -y software-properties-common
RUN add-apt-repository -y ppa:ondrej/php

# Atualize novamente os repositórios após adicionar o PPA
RUN apt-get update

# Instale o Apache
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y apache2

# Instale o PHP 8.3
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3

# Instale o módulo do Apache para PHP 8.3
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y libapache2-mod-php8.3

# Instale o PHP 8.3 CLI
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3-cli

# Instale o PHP 8.3 MySQL
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3-mysql

# Instale o PHP 8.3 mbstring
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3-mbstring

# Instale o PHP 8.3 XML
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3-xml

# Instale o PHP 8.3 CURL
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3-curl

# Instale o Git
RUN apt-get install -y git

# Instale o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure o Apache
RUN a2enmod php8.3
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/admin

# Limpeza do cache e configuração final
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Exponha a porta 80 para acessar o servidor web Apache
EXPOSE 80

# Inicie o Apache quando o contêiner for iniciado
CMD ["apache2ctl", "-D", "FOREGROUND"]
