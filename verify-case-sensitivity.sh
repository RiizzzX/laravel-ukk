#!/bin/bash

# Script untuk verifikasi case sensitivity sebelum deploy ke Linux
# Run: bash verify-case-sensitivity.sh

echo "=========================================="
echo "Case Sensitivity Verification Script"
echo "=========================================="
echo ""

ERRORS=0

# Check Model Files
echo "1. Checking Model Files..."
if [ -f "app/Models/ListLokasi.php" ]; then
    echo "   ✓ ListLokasi.php exists (correct)"
else
    echo "   ✗ ListLokasi.php NOT FOUND!"
    ERRORS=$((ERRORS + 1))
fi

if [ -f "app/Models/Listlokasi.php" ]; then
    echo "   ✗ Listlokasi.php found (should be ListLokasi.php)"
    ERRORS=$((ERRORS + 1))
fi

# Check for wrong case imports
echo ""
echo "2. Checking Import Statements..."
WRONG_IMPORTS=$(grep -r "use App\\\\Models\\\\Listlokasi" app/ database/ --include="*.php" 2>/dev/null)
if [ -z "$WRONG_IMPORTS" ]; then
    echo "   ✓ All imports use correct case"
else
    echo "   ✗ Found wrong case imports:"
    echo "$WRONG_IMPORTS"
    ERRORS=$((ERRORS + 1))
fi

# Check route names consistency
echo ""
echo "3. Checking Route Names..."
INCONSISTENT_ROUTES=$(grep -r "route(['\"]Admin\\." resources/views/ --include="*.blade.php" 2>/dev/null)
if [ -z "$INCONSISTENT_ROUTES" ]; then
    echo "   ✓ Route names are consistent (lowercase)"
else
    echo "   ⚠ Found potential case issues in routes:"
    echo "$INCONSISTENT_ROUTES"
fi

# Check file permissions (if running on Linux)
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    echo ""
    echo "4. Checking File Permissions..."
    
    if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
        echo "   ✓ storage/ and bootstrap/cache/ are writable"
    else
        echo "   ✗ Permission issues detected"
        echo "     Run: sudo chmod -R 775 storage bootstrap/cache"
        ERRORS=$((ERRORS + 1))
    fi
fi

# Check .env file
echo ""
echo "5. Checking Environment File..."
if [ -f ".env" ]; then
    echo "   ✓ .env file exists"
    
    # Check critical env vars
    if grep -q "APP_KEY=base64:" .env; then
        echo "   ✓ APP_KEY is set"
    else
        echo "   ✗ APP_KEY is not set. Run: php artisan key:generate"
        ERRORS=$((ERRORS + 1))
    fi
else
    echo "   ✗ .env file NOT FOUND. Copy from .env.example"
    ERRORS=$((ERRORS + 1))
fi

# Check composer autoload
echo ""
echo "6. Checking Composer Autoload..."
if [ -f "vendor/autoload.php" ]; then
    echo "   ✓ Composer dependencies installed"
else
    echo "   ✗ Composer dependencies NOT installed"
    echo "     Run: composer install"
    ERRORS=$((ERRORS + 1))
fi

# Summary
echo ""
echo "=========================================="
if [ $ERRORS -eq 0 ]; then
    echo "✓ ALL CHECKS PASSED!"
    echo "Project is ready for Linux deployment"
    exit 0
else
    echo "✗ FOUND $ERRORS ERROR(S)"
    echo "Please fix the issues above before deploying"
    exit 1
fi
