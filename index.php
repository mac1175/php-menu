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
            html,body{
            font-family: Helvetica;
            font-size: 8pt;
            }
            #top-menu-item{
                border-top: 1px solid white;
                border-left: 1px solid white;
            }
            ul{list-style: none;
               margin-left: 0;
              
               
           
            }
            a{text-decoration: none;color: white;}
            li{ background-color: #333333;color: white;}
            #top-menu-item > li {display: inline-block; float:left; width: 120px; height:20px;}
            #top-menu-item > li ul { display: block; float: none;position: relative;}
                #top-menu-item > li > ul{}
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
                echo '<ul id="top-menu-item">';
                $keys = array_keys($this->menu_items);
                foreach ($this->menu_items as $key => $value) {
                    $menu = $this->menu_items[$key];
                    $this->createMenuItem($menu);
                }
                echo '</ul>';
            }

            private function createMenuItem($menu) {

                echo "<li><a href='$menu->page' title='$menu->name'>$menu->name</a>";
                if (count($menu->getSubMenus()) > 0) {
                    echo '<ul>';
                    $subs = $menu->getSubMenus();
                    for ($m = 0; $m < count($subs); $m++
                    ) {
                        $sub = $subs[$m];
                        $this->createMenuItem($sub);
                    }
                    echo '</ul>';
                }
                echo '</li>';
            }

        }

//usage


        $mm = new MenuManager();
        ///creating a top menu item with the title 
        $mm->AddTopMenu("google", "http://google.com", "Google");
        //var_dump($mm);
        $mm->addSubMenu("google", "https://www.google.com/search?q=cats", "Search Cats");

        $mm->addTopMenu("reddit", "http://reddit.com", "Waste your time...");

        $x = $mm->addSubMenu("reddit", "http://reddit.com/r/wtf", "wtf?!");
        //var_dump($x);
        $x->addSubMenu("asdasd", "wergwrgw");

//var_dump($mm);
        $mm->displayLandingPageBulletList();
        ?>
    </body>
</html>
