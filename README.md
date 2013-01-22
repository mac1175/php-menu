Class Definitions:
class SubMenu
{
var $page;
var $name;
function SubMenu ($page_, $name_)
{
$this->page = $page_;
$this->name = $name_;
}
}
class TopMenu extends SubMenu
{
var $menu;
var $subMenus; // array of SubMenus
function TopMenu ($menu_, $page_, $name_)
{
$this->menu = $menu_;
$this->page = $page_;
$this->name = $name_;
$this->subMenus = array();
}
function addSubMenu ($page_, $name_)
{
array_push($this->subMenus, new SubMenu($page_, $name_));
}
}
class Menu
{
var $theMenu; // array of TopMenus
function Menu()
{
$this->theMenu = array();
}
function addTopLevelMenu($menu, $page, $name, $realMenu = 1)
{
if (isAuthorized ($name))
{
array_push($this->theMenu, new TopMenu ($menu, $page, $name));
}
}
function addSubMenu($menu, $page, $name)
{
if (isAuthorized ($name))
for ($i = 0; $i < count($this->theMenu); $i++)
{
if ($this->theMenu[$i]->menu == $menu)
{
$this->theMenu[$i]->addSubMenu($page, $name);
break;
}
}
}
function displayLandingPageBulletList($menu)
{
echo '<ul id="memberMenu">';
for ($i=0; $i < count($this->theMenu); $i++)
{
if ($this->theMenu[$i]->menu == $menu)
{
$topMenu = &$this->theMenu[$i];
for ($j = 0; $j < count($topMenu->subMenus); $j++)
{
echo '<li><a href="' . $topMenu->subMenus[$j]->page . '">' . $topMenu->subMenus[$j]->name . '</a></li>';
}
}
}
echo '</ul>';
}