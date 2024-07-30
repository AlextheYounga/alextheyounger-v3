git fetch master
git rebase origin/master
wait
yarn
php artisan db:seed --class=RepositorySeeder
php artisan db:seed --class=LanguageSeeder
yarn build