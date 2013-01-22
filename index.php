<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>test</title>
        <style type="text/css">
            /* These styles create the dropdown menus. */
            #menu {
                position: absolute;
                top: 0;
                left: 0;
                margin: 0;
                padding: 0;}
            #menu li {
                list-style: none;
                float: left; }
            #menu li a {
                display: block;
                padding: 3px 8px;
                text-transform: uppercase;
                text-decoration: none; 
                color: #999;
                font-weight: bold; }
            #menu li a:hover {
                color: #000; }
            #menu li ul {
                display: none;  }
            #menu li:hover ul, #menu li.hover ul {
                position: relative;
                display: inline-block;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 0; }
            #menu li:hover li, #menu li.hover li {
                float: left; }
            #menu li:hover li a, #menu li.hover li a {
                color: #000; }
            #menu li li a:hover {
                color: #357; }
        </style>
    </head>
    <body>
        <?php

        class Menu {

            var $page;
            var $name;
            var $subMenus = array(); // array of SubMenus

            function Menu($page_, $name_) {
                $this->page = $page_;
                $this->name = $name_;
            }

            function &addSubMenu($page, $name) {
                $menu = new Menu($page, $name);
                array_push($this->subMenus, $menu);
                return $menu;
            }

            function getSubMenus() {
                return $this->subMenus;
            }

        }

        class TopMenu extends Menu {

            function TopMenu($page_, $name_) {
                $this->page = $page_;
                $this->name = $name_;
                $this->subMenus = array();
            }

        }

        class MenuManager {

            var $menu_items = array();
            var $string_output = array();

            ///Usage: 
            ///  
            //  MenuManager->AddTopMenu('some-key','/some/page','Some Page');
            /// The first parameter is a unique key needed per top menu.
            /// returns a reference to the created top menu object so that you can manipulate
            /// it further downstream (i.e., changing page or name)
            /// If you need the object to change the title(name) of the menu item, for example, do the following:
            /// $newmenu = MenuManager->AddTopMenu('some-key','/some/page','Some Page');
            ///  ...
            /// $newmenu->name ='new name'; 
            ///
            /// The above code will be reflected in the output.
            public function &AddTopMenu($key, $page, $name) {
                $menu = new TopMenu($page, $name);
                $this->menu_items[$key] = $menu;
                return $menu;
            }

            private function appendOutput($val) {
                if (is_string($val)) {
                    array_push($this->string_output, $val);
                }
            }

            private function getOutput() {
                return implode("", $this->string_output);
            }

            /// Usage:
            /// MenuManager->addSubMenu('some-key','/some/page','Some Page')
            /// Adds a submenu to a *preexisting* menu item.  Also returns a reference to the
            /// new submenu object instance in case you need to manipulate elsewhere in the code.
            public function &addSubMenu($key, $page, $name) {
                if (array_key_exists($key, $this->menu_items)) {
                    return $this->menu_items[$key]->addSubMenu($page, $name);
                }
                echo "$key not found";
                return NULL;
            }

            ///Displays HTML output of menus
            function displayLandingPageBulletList() {
                $this->appendOutput('<ul id="menu">');
                $keys = array_keys($this->menu_items);
                foreach ($this->menu_items as $key => $value) {
                    $menu = $this->menu_items[$key];
                    $this->createMenuItem($menu);
                }
                $this->appendOutput('</ul>');
                return $this->getOutput();
            }

            private function createMenuItem($menu) {

                $this->appendOutput("<li><a href='$menu->page' title='$menu->name'>$menu->name</a>");
                if (count($menu->getSubMenus()) > 0) {
                    $this->appendOutput('<ul>');
                    $subs = $menu->getSubMenus();
                    for ($m = 0; $m < count($subs); $m++
                    ) {
                        $sub = $subs[$m];
                        $this->createMenuItem($sub);
                    }
                    $this->appendOutput('</ul>');
                }
                $this->appendOutput('</li>');
            }

        }

//usage


        $mm = new MenuManager();
        ///creating a top menu item with the title 


        $mm->AddTopMenu("account", "", "My Account");

        $mm->AddTopMenu("stuff", "http://news.google.com", "Stuff");

        $mm->AddTopMenu("google", "http://google.com", "Google");
        //var_dump($mm);
        $mm->addSubMenu("google", "https://www.google.com/search?q=cats", "Search Cats");

        $mm->addTopMenu("reddit", "http://reddit.com", "Waste your time...");

        $x = $mm->addSubMenu("reddit", "http://reddit.com/r/wtf", "wtf?!");
        //var_dump($x);
        $x->addSubMenu("asdasd", "wergwrgw");

//var_dump($mm);
        echo $mm->displayLandingPageBulletList();
        ?>
    </body>
</html>
