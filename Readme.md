## Inspect Diary
 _Real Estate Agent House Inspect Sceduling_

#### Install (Windows 10)
_probably, haven't tested this, I use this as a module_
1. Install Pre-Requisits
   1. Install PHP : http://windows.php.net/download/
      * Install the non threadsafe binary
        * Test by running php -v from the command prompt
          * If required install the VC++ runtime available from the php > download page
        * by default there is no php.ini (required)
          * copy php.ini-production to php.ini
   2. Install Git : https://git-scm.com/
      * Install the *Git Bash Here* option
   3. Install Composer : https://getcomposer.org/
2. Download ZIP (this isn't in packagist) and expand, then in that dir ...
2. Install dependencies &amp; run
   ```
   cd <my-project>
   composer update
   run.cmd
   ```

   ... the result is visible at http://localhost/
