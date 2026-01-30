# Laravel Project - Deployment Guide

## 📦 Setup Lokal

### Prerequisites
- PHP 8.x
- Composer
- MySQL
- Node.js & NPM
- Python (untuk ML API)

### Installation

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd web-tugas-akhir1
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env`:
   ```env
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start server**
   ```bash
   php artisan serve
   ```

---

## 🚀 Deployment

### Opsi 1: Shared Hosting (cPanel)

1. Upload semua file kecuali `/vendor` dan `/node_modules`
2. Buat database MySQL via cPanel
3. Copy `.env.example` → `.env` dan edit kredensial
4. Install dependencies via SSH atau Composer di cPanel
5. Point domain ke folder `/public`

### Opsi 2: Laravel Cloud

1. Push code ke GitHub
2. Connect repository di Laravel Cloud dashboard
3. Set environment variables
4. Deploy otomatis

### Opsi 3: Railway

1. Install Railway CLI: `npm install -g @railway/cli`
2. Login: `railway login`
3. Init: `railway init`
4. Add MySQL: `railway add`
5. Deploy: `railway up`

### Opsi 4: Laravel Forge + DigitalOcean

1. Buat server di Forge
2. Deploy dari Git atau upload manual
3. Configure environment variables
4. Setup database

---

## ⚙️ Environment Variables

**Required variables:**
- `APP_KEY` - Generate dengan `php artisan key:generate`
- `DB_*` - Database credentials
- `WHATSAPP_API_KEY` - API key untuk WhatsApp integration
- `ML_SCRIPT_PATH` - Path ke Python ML scripts

**Lihat `.env.example` untuk daftar lengkap.**

---

## 🔐 Security Notes

- **JANGAN** upload file `.env` ke GitHub
- Gunakan `APP_DEBUG=false` di production
- Set `APP_ENV=production` di production
- Rotate API keys secara berkala

---

## 📝 License

[Your License Here]
