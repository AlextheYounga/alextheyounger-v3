git fetch origin master
wait
git rebase origin/master
wait
nvm install
wait
yarn
wait
php artisan db:seed --class=RepositorySeeder
wait
php artisan db:seed --class=LanguageSeeder
wait
yarn build