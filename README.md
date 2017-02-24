#DirSys
DirSys is a interface to index your files of the any folder of server 

All files is required to system works except the `example` folder

#####How to use
Clone this repository in index folder and put yours files inside

if want exclude any path of files list. In `index.php` you can add exclude path:
```
$ds->addExcludePath([
  '.gitignore',
  '.git',
  '.gitattributes',
  'error_log',
  'your path here'
]);
```

---

**Versions**

v0.9.0 - release: 22/02/2017

Change log:
- First release

