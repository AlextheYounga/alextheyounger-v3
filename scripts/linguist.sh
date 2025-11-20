#!/bin/bash
source .env

# Run the github-linguist library to get the language statistics for each repository
# We will start in Bash and end in PHP
# Prerequisites:
# - Install github-linguist: https://github.com/github-linguist/linguist
# - Set your environment variables in the .env files to point to the correct directories
# - Create a list of emails to be used in git commit searches. This is used to identify repositories where you've made commits
# - If you're using rbenv, make sure the version of Ruby which has github-linguist installed is the one being used

# Define the root directory to start searching from
ROOT_DIR=$DEVELOPMENT_FOLDER # from .env
STORAGE_FOLDER="$APP_DIRECTORY/storage/app/public"
LINGUIST_FOLDER="$STORAGE_FOLDER/linguist"
AUTHOR_EMAILS="$STORAGE_FOLDER/git-emails.txt"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

mkdir -p $LINGUIST_FOLDER

repo_contains_author() {
    # Find all repositories where I've made commits
    # Read from the git-emails.txt file to get a list of author emails to check for
    IFS=$'\n' read -d '' -r -a author_ids < "$AUTHOR_EMAILS"

    for id in "${author_ids[@]}"; do
        # Count the number of characters in output but limit to 1.
        # That way if the output is empty, it will return 0 and if it has output, 1.
        local author_search=$(git log --author="$id" --oneline | head -c1 | wc -c)
        if [[ $author_search -eq 1 ]]; then
            echo -e "${GREEN}Found author commits in $dir_path${NC}"
            if [[ $id == "alex" ]]; then
                echo -e "${YELLOW}Warning: author id is just 'alex'. Recommend changing ${NC}"
            fi
            return 0
        fi
    done

    echo -e "${RED}No author commits in $dir_path, skipping${NC}"
    return 1
}

run_linguist() {
    local git_dir="$1"

    # Skip cloned repositories
    if [[ "$git_dir" == *"__cloned__"* || "$git_dir" == *"/Cloned/"* ]]; then
        return
    fi

    local dir_path="$(dirname "$git_dir")"

    cd $dir_path
    echo "Running linguist in $dir_path"
    local repo_name=$(basename $(git rev-parse --show-toplevel))
    local output_file="$LINGUIST_FOLDER/$repo_name.txt"

    # Check if I've made commits to the repository
    if repo_contains_author; then
        local repo_size=$(du -sh .git)

        echo "$dir_path" > $output_file
        github-linguist --json >> $output_file
        echo $repo_size >> $output_file
    fi

    cd - > /dev/null # This guy wants to output stuff, so we need to suppress it
}

# Recursively find all .git folders
{ find "$ROOT_DIR" -type d -name '.git' -exec printf '%s\0' {} + | while IFS= read -ru3 -d '' file; do
    run_linguist "$file"
done 3<&0 <&4 4<&-; } 4<&0

# Run the python script to parse the output
php artisan app:linguist
