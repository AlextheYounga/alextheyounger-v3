const fs = require('fs');


function minifyFile(jsonFile) {
  fs.readFile(jsonFile, 'utf8', (err, jsonString) => {
    if (err) {
      throw new Error(`Error minifying JSON file: ${err}`);
    }

    try {
      // Step 2: Parse the string to a JavaScript object
      const data = JSON.parse(jsonString);

      // Step 3: Stringify the object
      const minifiedJsonString = JSON.stringify(data);

      // Optional: Write the minified JSON string back to a file
      fs.writeFile(jsonFile, minifiedJsonString, (err) => {
        if (err) {
          throw new Error(`Error minifying JSON file: ${err}`);
        }
      });
    } catch (_) {}
  });
}

module.exports = minifyFile;