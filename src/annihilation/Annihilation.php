<?php
namespace annihilation;

use annihilation\game\GameManager;
use function is_dir;
use function mkdir;

/**
 * Class Annihilation
 * @package annihilation
 */
class Annihilation extends \pocketmine\plugin\PluginBase {
  
  /** @var Annihilation $plugin */
  private static Annihilation $plugin;
  
  /** @var GameManager $gameManager */
  private GameManager $gameManager;
  
  /**
   * @return void
   */
  public function onLoad(): void {
    self::$plugin = $this;
    $this->gameManager = new GameManager();
  }
  
  /**
   * @return void
   */
  public function onEnable(): void {
    if (!is_dir($this->getDataFolder() . "games")) {
      mkdir($this->getDataFolder() . "games");
    }
    if (!is_dir($this->getDataFolder() . "worlds")) {
      mkdir($this->getDataFolder() . "worlds");
    }
    
    
  }
  
  /**
   * @return Annihilation
   */
  public static function getPlugin(): Annihilation {
    return self::$plugin;
  }
  
  /**
   * @return GameManager
   */
  public function getGameManager(): GameManager {
    return $this->gameManager;
  }
}
