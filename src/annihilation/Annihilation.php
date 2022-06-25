<?php
namespace annihilation;

use annihilation\game\GameManager;

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
