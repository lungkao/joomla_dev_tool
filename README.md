File Purpose: scan-unused-final.php

This PHP script is designed to scan unused files within the Joomla component com_puchchafaq.
It provides the following main functionalities:
	•	Scan and identify unused files:
It analyzes all PHP, XML, INI, CSS, and JS files under /administrator/components/com_puchchafaq, skipping index.html, and detects files that are not referenced by other files.
	•	Generate a report:
It creates a file named unused_files.txt listing all the detected unused files.
	•	Export a backup ZIP:
It packages the unused files into a ZIP archive called unused_files_backup.zip.
	•	Optional file deletion:
After scanning and backup, there is a web interface with a button that allows the user to delete all unused files at once for cleanup.
	•	Safety measures:
Before deletion, it checks paths carefully to avoid accidentally deleting files outside the intended folder.

⸻

Technical Details:
	•	Scans using RecursiveDirectoryIterator.
	•	Only looks at files with extensions .php, .xml, .ini, .css, .js.
	•	Files are considered used if their filename appears as a string inside any PHP or JS file.
	•	Provides a simple HTML interface with a delete button after scanning.
	•	Skips files named index.html intentionally to avoid deleting security placeholders.

⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻

File Purpose: export_language_keys_2cols.php

This PHP script is designed to scan and extract language keys used in the Joomla component com_puchchafaq, and display them neatly in a 2-column web interface: one for the Site area and one for the Administrator area.

It provides the following features:
	•	Scan language keys:
It recursively searches .php, .xml, and .html files under the /components/com_puchchafaq (Site) and /administrator/components/com_puchchafaq (Admin) folders for language constants matching the pattern COM_PUCHCHAFAQ_[A-Z0-9_]+.
	•	Display results in two tables:
The page displays two Bootstrap-styled tables — one for Site keys and one for Admin keys — side by side (2 columns).
	•	Export functionality:
Each table includes a button that allows the user to export all found keys into a plain text file (.txt) directly from the browser.
	•	Responsive design:
It uses Bootstrap 5 to ensure the page is responsive and looks clean.
	•	Safe HTML escaping:
All language keys are properly escaped when displayed to prevent XSS vulnerabilities.

⸻

Technical Details:
	•	The script uses RecursiveIteratorIterator and RecursiveDirectoryIterator to scan all subdirectories.
	•	It detects language keys using regular expressions.
	•	It provides an instant client-side export (JavaScript Blob) without needing to reload or regenerate files on the server.
	•	Bootstrap 5 is loaded via CDN for fast and easy layout styling.
⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻⸻
File Purpose: compare-files.php

This PHP script is designed to compare the contents of two text files — file1.txt and file2.txt — and display the differences and similarities in a web page.

It provides the following main features:
	•	File comparison:
It loads both file1.txt and file2.txt, reads their lines, and compares them to find:
	•	Lines that exist only in file1.txt
	•	Lines that exist only in file2.txt
	•	Lines that exist in both files
	•	User-friendly web interface:
It displays the comparison results clearly using Bootstrap 5:
	•	A red panel for lines only in file1
	•	A green panel for lines only in file2
	•	A blue panel for lines common to both
	•	Safe output:
All displayed lines are properly HTML-escaped to prevent XSS vulnerabilities.
	•	File existence check:
If file1.txt or file2.txt is missing, the script will stop and show an error message.

⸻

Technical Details:
	•	Reads files line-by-line using file() function.
	•	Compares contents using PHP’s array_diff() and array_intersect().
	•	Uses Bootstrap 5 for styling and responsive layout.
	•	Easy to extend if you want to allow file uploads or dynamic comparison in the future.

⸻

✅ Summary:
This tool is useful for checking added, removed, and unchanged entries between two text files in a simple and visual way.
