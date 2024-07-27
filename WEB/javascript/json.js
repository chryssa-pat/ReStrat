const mysql = require('mysql');
const fs = require('fs');

// Database connection details
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'WEB'
});

// Connect to the database
connection.connect(err => {
  if (err) {
    console.error('Error connecting to the database:', err);
    return;
  }
  console.log('Connected to the database');

  // Read JSON file
  fs.readFile('path_to_your_json_file.json', 'utf8', (err, jsonData) => {
    if (err) {
      console.error('Error reading JSON file:', err);
      return;
    }
    
    // Debugging: Print JSON data
    console.log('JSON Data:', jsonData);

    let data;
    try {
      data = JSON.parse(jsonData);
    } catch (parseErr) {
      console.error('Error parsing JSON data:', parseErr);
      return;
    }

    // Debugging: Print JSON structure
    console.log('Parsed Data:', data);

    if (!Array.isArray(data)) {
      console.error('Error: JSON data is not an array');
      return;
    }

    // Insert categories into CATEGORIES table
    data.forEach(category_data => {
      const category_name = category_data.category;
      const items = category_data.items;

      // Insert category into CATEGORIES table
      const categoryQuery = 'INSERT INTO CATEGORIES (category_name) VALUES (?)';
      connection.query(categoryQuery, [category_name], (err, result) => {
        if (err) {
          console.error('Error inserting category:', err);
          return;
        }
        console.log(`Category inserted: ${category_name}`);

        const category_id = result.insertId;

        // Insert items into PRODUCTS table
        items.forEach(item_name => {
          const productQuery = 'INSERT INTO PRODUCTS (category_id, item, description, available) VALUES (?, ?, "", 1)';
          connection.query(productQuery, [category_id, item_name], (err, result) => {
            if (err) {
              console.error('Error inserting product:', err);
              return;
            }
            console.log(`Product inserted: ${item_name}`);
          });
        });
      });
    });

    console.log('Data insertion process completed.');
    connection.end();
  });
});
