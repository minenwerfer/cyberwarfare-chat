# CyberWarfare Chat (CWC)

A minimal chat intended to run on any server and be supported by text-mode web browsers (well tested on Links2).

- HTML4 only interface (runs well on lynx-like browsers)
- No configuration needed
- No PHP extensions or databases needed (everything is stored on filesystem)
- All data is encrypted and unrecoverable without the chosen key
- Comes w/ a very flexibe plugin API
- Clene-focused

## To write your own plugin:

Create a class in the namespace \Plugin that inherits Plugin, then do wathever you want in the ::install() method. Late,r, you'll need to load the plugin in the /index.php file.

Available interfaces:

- \Session - current session data
- \Command - parses commands and sends output

For now, you'll need to look at the own source code to view available methods. I'll document everything as soon as I can.

## Screenshots

![Peek 2021-03-30 16-15](https://user-images.githubusercontent.com/80406377/113051469-015c6b80-9174-11eb-8b1c-c93207bdf2be.gif)
![Screenshot_2021-03-30_16-20-59](https://user-images.githubusercontent.com/80406377/113051497-091c1000-9174-11eb-8f95-14ee6361eb6a.png)
