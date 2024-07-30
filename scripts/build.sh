git fetch master
git rebase origin/master
nvm install
wait
yarn
php artisan db:seed --class=RepositorySeeder
php artisan db:seed --class=LanguageSeeder
yarn build