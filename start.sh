#!/bin/bash

echo "🚀 Starting HRMS + SPK AHP System"
echo "=================================="

# Check if backend is setup
if [ ! -f "hrms-spk-backend/.env" ]; then
    echo "⚠️  Backend .env not found. Setting up backend..."
    cd hrms-spk-backend
    cp .env.example .env
    composer install
    php artisan key:generate
    touch database/database.sqlite
    php artisan migrate
    php artisan db:seed
    cd ..
fi

# Check if frontend is setup
if [ ! -d "hrms-frontend/node_modules" ]; then
    echo "⚠️  Frontend node_modules not found. Installing..."
    cd hrms-frontend
    npm install
    cd ..
fi

echo ""
echo "✅ Setup complete!"
echo ""
echo "Starting servers..."
echo ""

# Start backend
cd hrms-spk-backend
php artisan serve &
BACKEND_PID=$!
cd ..

# Wait for backend to start
sleep 3

# Start frontend
cd hrms-frontend
npm run dev &
FRONTEND_PID=$!
cd ..

echo ""
echo "✅ Servers started!"
echo ""
echo "📍 Backend API: http://localhost:8000"
echo "📍 Frontend: http://localhost:3000"
echo ""
echo "🔑 Login credentials:"
echo "   Email: admin@hrms.com"
echo "   Password: password123"
echo ""
echo "Press Ctrl+C to stop all servers"
echo ""

# Wait for Ctrl+C
trap "kill $BACKEND_PID $FRONTEND_PID; exit" INT
wait
