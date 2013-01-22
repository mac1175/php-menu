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

        </style>
    </head>
    <body>
        <?php

        class Menu {

            var $page;
            var $name;
            var $subMenus=array(); // array of SubMenus

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

            public function &AddTopMenu($key, $page, $name) {
                $menu = new TopMenu($page, $name);
                $this->menu_items[$key] = $menu;
                return $menu;
            }

            public function &addSubMenu($key, $page, $name) {
                if (array_key_exists($key, $this->menu_items)) {
                    return $this->menu_items[$key]->addSubMenu($page, $name);
                }
                echo "$key not found";
                return NULL;
            }

            function displayLandingPageBulletList() {
                echo '<ul class="top-menu-item">';
                $keys = array_keys($this->menu_items);
                foreach ($this->menu_items as $key => $value) {
                    $menu = $this->menu_items[$key];
                    $this->createMenuItem($menu);
                }
                echo '</ul>';
            }

            private function createMenuItem($menu) {

                echo "<li><a href='$menu->page' >$menu->name</a>";
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
        $mm->AddTopMenu("topmenu1", "/some/page", "top menu 1");
        //var_dump($mm);
        $mm->addSubMenu("topmenu1", "/some/page", "sub menu 1");

        $mm->addTopMenu("topmenu2", "/some/page", "top menu 2");

        $x= $mm->addSubMenu("topmenu1", "/some/page", "sub menu 2");
var_dump($x);
$x->addSubMenu("asdasd","wergwrgw");

//var_dump($mm);
        $mm->displayLandingPageBulletList();
        ?>
    </body>
</html>
