source .env
rsync -av $PROD_IP:$PROD_DB_PATH ./storage/app/backups/