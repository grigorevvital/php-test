<?php
require_once('interface.php');

class FileBrowser implements __FileBrowser {

  private $currentRootPath;
  private $currentPath;
  private $extensions;

  function __construct($rootPath, $currentPath = null, array $extensionFilter = [])
  {
      $this->currentRootPath = is_dir($rootPath) ? $rootPath : getcwd();
      $this->SetCurrentPath($currentPath);
      $this->extensions = $extensionFilter;
  }

  /**
   * Set private root path
   */
  public function SetRootPath($rootPath)
  {
      $this->currentRootPath = $rootPath;
  }

  /**
   * Set private current path
   */
  public function SetCurrentPath($currentPath)
  {
    if ($this->currentRootPath && $currentPath)
      $this->currentPath = $this->currentRootPath . '/'. $currentPath;
  }

  /**
   * Set private extension filter
   */
  public function SetExtensionFilter(array $extensionFilter)
  {
    $this->extensions = $extensionFilter;
  }

  /**
   * Get files using currently-defined object properties
   * @return array Array of files within the current directory
   */
  public function Get()
  {
      $skip = array_merge($this->extensions, ['.', '..']);
      $files = array_diff(scandir($this->currentPath ?? $this->currentRootPath), $skip, $this->extensions ?? []);
      $links = [];
      $extensions = [];
      //make go up
      if (count($files) > 0) {
        //make go up link
        if (!is_null($this->currentRootPath)) {
            $links_arr = explode("/", $this->currentRootPath);
            $sub_link = end($links_arr);
            $root = implode("/", array_diff($links_arr, [$sub_link]));
            $type = $this->extensions != [] ? '&type='.implode(",", $this->extensions) : '';
            $links[] = $this->currentRootPath == getcwd() ? "<a href='http://".$_SERVER['HTTP_HOST']."'>GO UP</a><br>" :
                  "<a href='/?root=$root&sub=$sub_link".$type."'>GO UP</a>".'<br>';
        }
        //build links
        foreach($files as $file) {
            $file_path = isset($this->currentPath) ? $this->currentPath.'/'.$file : $this->currentRootPath.'/'.$file;
            if (is_dir($file_path)) {
               $links[] = "<a href='/?root=$this->currentPath&sub=$file".$type."'>$file</a>".'<br>';
            } else  {
                if ($this->extensions != [])
                  $links[] = in_array($this->extensions, pathinfo($filename, PATHINFO_EXTENSION)) ? $file.'<br>' : null;
                else
                  $links[] = $file.'<br>';
            }
        }

      } else {
        //nothing found  we go up
         if (!is_null($this->currentRootPath)) {
            $links[] = "<a href='$this->currentRootPath'> Sorry no files found, click to go UP </a>";
         }
      }
      //return array
      return $links;
  }

}
