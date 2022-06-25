<?php
namespace annihilation\game;

use annihilation\game\Game;
use pocketmine\utils\Config;
use function count;

/**
 * Class GameManager
 * @package annihilation\game
 */
class GameManager {
  
  /** @var Game[] $games */
  private static array $games = [];
  
  /**
   * @param int $id
   * @return Game|null
   */
  public function getGame(int $id): ?Game {
    $game = null;
    foreach ($this->getGames() as $games) {
      if ($games->getID() == $id) {
        $game = $games;
      }
    }
    return $game;
  }
  
  /**
   * @return void
   */
  public function createGame(World $world): void {
    $id = count($this->getGames()) + 1;
    $game = new Game($id, $world);
    $config = new Config(Annihilation::getPlugin()->getDataFolder() . "/games/" . "Game-" . $id . ".yml", Config::YAML);
    $data = [
      "id" => $game->getID(),
      "world" => $game->getWorld()->getFolderName(),
      "enabled" => $game->isEnabled(),
      "status" => $game->getStatus(),
      "phase" => $game->getPhase()
      ];
    $config->setAll($data);
    $config->save();
    self::$games[] = $game;
  }
  
  /**
   * @return array
   */
  public function getGames(): array {
    return self::$games;
  }
}
