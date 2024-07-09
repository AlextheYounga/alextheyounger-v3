git fetch master
git rebase origin/master
wait
php artisan db:seed --class=RepositorySeeder
php artisan db:seed --class=LanguageSeeder
yarn build