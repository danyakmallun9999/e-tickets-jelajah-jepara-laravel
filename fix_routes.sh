#!/bin/bash

# Define paths
PATHS="resources/views app/Http/Controllers routes/web.php"

# Booking Routes (Single Quotes)
find $PATHS -type f -exec sed -i "s/route('tickets.book'/route('booking.store'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.checkout'/route('booking.checkout'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.process-checkout'/route('booking.process'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.confirmation'/route('booking.confirmation'/g" {} +

# Booking Routes (Double Quotes)
find $PATHS -type f -exec sed -i 's/route("tickets.book"/route("booking.store"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.checkout"/route("booking.checkout"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.process-checkout"/route("booking.process"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.confirmation"/route("booking.confirmation"/g' {} +

# Payment Routes (Single Quotes)
find $PATHS -type f -exec sed -i "s/route('tickets.payment'/route('payment.show'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.process-payment'/route('payment.process'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.payment.status'/route('payment.status'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.payment.success'/route('payment.success'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.payment.failed'/route('payment.failed'/g" {} +

# Payment Routes (Double Quotes) - FIXED: Removed closing parenthesis to match params
find $PATHS -type f -exec sed -i 's/route("tickets.payment"/route("payment.show"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.process-payment"/route("payment.process"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.payment.status"/route("payment.status"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.payment.success"/route("payment.success"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.payment.failed"/route("payment.failed"/g' {} +

# Payment Actions (Single Quotes)
find $PATHS -type f -exec sed -i "s/route('tickets.check-status'/route('payment.check'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.cancel'/route('payment.cancel'/g" {} +
find $PATHS -type f -exec sed -i "s/route('tickets.retry-payment'/route('payment.retry'/g" {} +

# Payment Actions (Double Quotes)
find $PATHS -type f -exec sed -i 's/route("tickets.check-status"/route("payment.check"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.cancel"/route("payment.cancel"/g' {} +
find $PATHS -type f -exec sed -i 's/route("tickets.retry-payment"/route("payment.retry"/g' {} +

echo "Routes refactored successfully (Parameters supported)."
