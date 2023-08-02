# PHP Conversion Web Service

This PHP Conversion Web Service provides two methods for file conversion and PDF optimization. It supports converting various file formats such as DOCX, XLSX, and LibreOffice handled formats to PDF. Additionally, it allows you to optimize PDF files with different quality presets.

## Docker Version

For your convenience, a Docker version of this PHP Conversion Web Service is available. With Docker, you can easily containerize the application and deploy it on any system that supports Docker.

### Docker Setup

1. Make sure you have Docker installed on your system. You can download Docker from the official website: [https://www.docker.com/get-started](https://www.docker.com/get-started).

2. Clone the repository or obtain the Docker image for the PHP Conversion Web Service.

3. Build the Docker image using the provided Dockerfile:
   ```bash
   docker build -t convertws .
   ```

4. Run the Docker container, binding it to a specific port on your host machine:
   ```bash
   docker run -d -p 8080:80 convertws
   ```

5. The PHP Conversion Web Service should now be accessible at `http://localhost:8080/convert2pdf.php` and `http://localhost:8080/optimizepdf.php`.

## Docker Compose Support

Alternatively, you can use Docker Compose to manage the PHP Conversion Web Service along with its dependencies, such as a web server (e.g., Apache or Nginx) and other services if needed.

### Docker Compose Setup

1. Make sure you have Docker Compose installed on your system. You can find installation instructions here: [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/).

2. Start the services using Docker Compose:
   ```bash
   docker-compose up -d --build
   ```

5. The PHP Conversion Web Service should now be accessible at `http://localhost:8080/convert2pdf.php` and `http://localhost:8080/optimizepdf.php`.

## API Endpoints

### 1. /convert2pdf.php

Converts any DOCX, XLSX, or LibreOffice handled format to PDF.

**Endpoint:** `/convert2pdf.php`

**Method:** POST

**Parameters:**
- `input` (required): The input file to be converted. Supported formats include DOCX, XLSX, and LibreOffice handled formats.

**Example Call using curl:**
```bash
curl -X POST -F "input=@/path/to/your/file.docx" http://localhost:8080/convert2pdf.php
```

### 2. /optimizepdf.php

Optimizes a PDF with a 'preset' query parameter that can have values of 'screen' (worst quality), 'ebook' (medium quality), or 'printer' (high quality).

**Endpoint:** `/optimizepdf.php`

**Method:** POST

**Parameters:**
- `input` (required): The input PDF file to be optimized.
- `preset` (required): The quality preset for the PDF optimization. Values: 'screen', 'ebook', or 'printer'.

**Example Call using curl:**
```bash
curl -X POST -F "input=@/path/to/your/file.pdf" -F "preset=printer" http://localhost:8080/optimizepdf.php
```
