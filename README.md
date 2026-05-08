# ⚽ Quiniela 2026 — Mundial FIFA

Aplicación Laravel para la quiniela del Mundial 2026 entre compañeros de trabajo.

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (incluido) o MySQL/PostgreSQL

## Instalación rápida

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Instalar dependencias JS y compilar assets
npm install && npm run build

# 3. Copiar y configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Crear base de datos y cargar datos
php artisan migrate:fresh --seed

# 5. Iniciar servidor
php artisan serve
```

Abre http://localhost:8000

## Credenciales de admin

- **Email:** admin@quiniela.com
- **Password:** quiniela2026

> Cambia la contraseña en producción.

## Estructura del sistema de puntos

### Quiniela Maestra (220 pts en juego)
| Categoría | Puntos |
|-----------|--------|
| Campeón | 20 pts |
| Subcampeón | 15 pts |
| Tercer lugar | 10 pts |
| Balón de Oro | 15 pts |
| Bota de Oro | 15 pts |
| Guante de Oro | 10 pts |
| Mejor Joven | 10 pts |
| País Sorpresa | 10 pts |
| Más goleadora | 10 pts |
| Menos goleada | 10 pts |
| Total de goles | hasta 15 pts |
| Clasificados Octavos | 1 pt c/u × 16 |
| Clasificados Cuartos | 2 pts c/u × 8 |
| Clasificados Semis | 5 pts c/u × 4 |
| Finalistas | 14 pts c/u × 2 |

### Por Partido (hasta 22 pts)
| Predicción | Puntos |
|-----------|--------|
| Marcador exacto | 10 pts |
| Ganador correcto | 4 pts |
| Diferencia de goles exacta | 6 pts |
| Primer goleador (equipo) | +3 pts |
| Tarjeta roja | +2 pts |
| Prórroga | +4 pts |
| Penales | +3 pts |

## Flujo de uso

1. **Compañeros** se registran en `/register`
2. Cada uno completa su **Quiniela Maestra** antes del inicio del torneo
3. Antes de cada partido, completan su **pronóstico por partido**
4. El **admin** ingresa los resultados reales en `/admin`
5. Los puntos se calculan automáticamente
6. La **tabla de posiciones** se actualiza en tiempo real

## Configuración para producción

```bash
# Usar MySQL en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiniela2026
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
