# pinmarker
created using codeigniter 3

# Run
php -S 127.0.0.1:8080  

# Deploy
http://127.0.0.1:8080/public/

https://pinmarker-test.leonardhors.com/public/
or 
https://pinmarker.leonardhors.com/public/

# Indexing
CREATE INDEX idx_pin_date
ON weather_forecast_cache(related_pin_id, start_date);

CREATE INDEX idx_coordinate
ON weather_forecast_cache(latitude, longitude);

CREATE INDEX idx_date
ON weather_forecast_cache(start_date);