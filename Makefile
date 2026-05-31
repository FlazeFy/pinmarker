run-dev:
	DB_NAME=pinmarker_v2_dev php -S 127.0.0.1:8080

run-test:
	DB_NAME=pinmarker_v2_test php -S 127.0.0.1:8090

run:
	DB_NAME=pinmarker_v2_dev php -S 127.0.0.1:8080 > dev.log 2>&1 &
	DB_NAME=pinmarker_v2_test php -S 127.0.0.1:8090 > test.log 2>&1 &
	@echo "Dev:  http://127.0.0.1:8080 (pinmarker_v2_dev)"
	@echo "Test: http://127.0.0.1:8090 (pinmarker_v2_test)"

stop:
	pkill -f "php -S 127.0.0.1:8080" || true
	pkill -f "php -S 127.0.0.1:8090" || true