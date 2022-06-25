<?php
namespace annihilation;

/**
 * Class Annihilation
 * @package annihilation
 */
class Annihilation extends \pocketmine\plugin\PluginBase {
  
  /** @var Annihilation $plugin */
  private static Annihilation $plugin;
  
  /**
   * @return void
   */
  public function onLoad(): void {
    self::$plugin = $this;
  }
  
  /**
   * @return Annihilation
   */
  public static function getPlugin(): Annihilation {
    return self::$plugin;
  }
}
