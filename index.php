<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>test</title>
    </head>
    <body>
        <?php

        class Menu {

            var $page;
            var $name;

            function Menu($page_, $name_) {
                $this->page = $page_;
                $this->name = $name_;
            }

        }

        class TopMenu extends Menu {
 
            var $subMenus; // array of SubMenus

            function TopMenu($page_, $name_) {
                $this->page = $page_;
                $this->name = $name_;
                $this->subMenus = array();
            }

            function addSubMenu($menu) {
                array_push($this->subMenus, $menu);
            }
            function getSubMenus(){
                return $this->subMenus;
            }

        }

        class MenuManager {

            var $menu_items;

            function MenuManager($items) {
                $this->menu_items = $items;
                 
            }

            function displayLandingPageBulletList() {
                echo '<ul id="memberMenu">';
                for ($i = 0; $i < count($this->menu_items); $i++) {
                    $menu = $this->menu_items[$i];
                    $this->createMenuItem($menu);
                }
                echo '</ul>';
            }

            function createMenuItem($menu) {
                
                echo "<li><a href='$menu->page' >$menu->name</a>";
                if (is_subclass_of($menu, 'Menu') && count($menu->getSubMenus()) > 0) {
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

        $tm_array = array();
        for ($tc = 0; $tc < 10; $tc++) {
            $n="tm$tc";
            $tm = new TopMenu($n, $n);
            for ($x = 0; $x < 5; $x++) {
                $sn=$n."_sub$x";
                $tm->addSubMenu(new Menu($sn, $sn));
            }
            array_push($tm_array, $tm);
        }
        
        $mm = new MenuManager($tm_array);
        //var_dump($mm);
        $mm->displayLandingPageBulletList();
        ?>
    </body>
</html>
