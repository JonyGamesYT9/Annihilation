<?php
namespace annihilation\game;

use annihilation\Annihilation;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use pocketmine\Server;
use function strtolower;
use function is_file;
use function unlink;
use function realpath;
use function strlen;
use function substr;
use function file_exists;

/**
 * Class Game 
 * @package annihilation\game
 */
class Game {
  
  /** @var int[] $health */
  private array $health = [
    "blue" => 75,
    "red" => 75,
    "green" => 75,
    "yellow" => 75
    ];
  
  /** @var Player[] $players */
  private array $players = [];
  
  /** @var Vector3[] $spawns */
  private array $spawns = [];
  
  /** @var int $gameId */
  private int $gameId;
  
  /** @var World $world */
  private World $world;
  
  /** @var bool $enabled */
  private bool $enabled = false;
  
  /** @var int $phase */
  private int $phase = self::PHASE_1;
  
  /** @var int $status */
  private int $status = self::WAITING;
  
  /** @var int WAITING */
  public const WAITING = 0;
  
  /** @var int STARTING */
  public const STARTING = 1;
  
  /** @var int INGAME*/
  public const INGAME = 2;
  
  /** @var int ENDING */
  public const ENDING = 3;
  
  /** @var int PHASE_1 */
  public const PHASE_1 = 1;
  
  /** @var int PHASE_2 */
  public const PHASE_2 = 2;
  
  /** @var int PHASE_3 */
  public const PHASE_3 = 3;
  
  /** @var int PHASE_4 */
  public const PHASE_4 = 4;
  
  /** @var int PHASE_5 */
  public const PHASE_5 = 5;
  
  /**
   * Game constructor.
   * @param int $gameId
   */
  public function __construct(int $gameId, World $world) {
    $this->gameId = $gameId;
    $this->world = $world;
  }
  
  /**
   * @return int
   */
  public function getID(): int {
    return $this->gameId;
  }
  
  /**
   * @return World 
   */
  public function getWorld(): World {
    return $this->world;
  }
  
  /**
   * @param string $teamName
   * @return int
   */
  public function getNexusHealth(string $teamName): int {
    return $this->health[strtolower($teamName)];
  }
  
  /**
   * @param int $phase
   * @return void
   */
  public function setPhase(int $phase = self::PHASE_1): void {
    $this->phase = $phase;
  }
  
  /**
   * @return int
   */
  public function getPhase(): int {
    return $this->phase;
  }
  
  /**
   * @param int $status
   * @return void
   */
  public function setStatus(int $status = self::WAITING): void {
    $this->status = $status;
  }
  
  /**
   * @return int
   */
  public function getStatus(): int {
    return $this->status;
  }
  
  /**
   * @param bool $result 
   * @return void
   */
  public function setEnabled(bool $result = true): void {
    $this->enabled = $result;
  }
  
  /**
   * @return bool
   */
  public function isEnabled(): bool {
    return $this->enabled;
  }
  
  /**
   * @return array 
   */
  public function getPlayers(): array {
    return $this->players;
  }
  
  /** 
   * @return array
   */
  public function getSpawns(): array {
    return $this->spawns;
  }
  
  /**
   * @param string $teamName
   * @return Vector3
   */
  public function getSpawn(string $teamName): Vector3 {
    return $this->getSpawns()[strtolower($teamName)];
  }
  
  /**
   * @return void
   * @author GamakCZ (https://www.github.com/GamakCZ)
   */
  public function saveWorld(): void {
    $world = $this->getWorld();
    $worldPath = Server::getInstance()->getDataPath() . "worlds/" . $world->getFolderName();
    $zipPath = Annihilation::getPlugin()->getDataFolder() . "worlds/" . $world->getFolderName() . ".zip";
    $zip = new \ZipArchive();
    if (is_file($zipPath)) {
      unlink($zipPath);
    }
    $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(realpath($worldPath)), \RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $file) {
      if ($file->isFile()) {
        $filePath = $file->getPath() . DIRECTORY_SEPARATOR . $file->getBasename();
        $localPath = substr($filePath, strlen(Server::getInstance()->getDataPath() . "worlds"));
        $zip->addFile($filePath, $localPath);
      }
    }
    $zip->close();
  }
  
  /**
   * @return void
   * @author GamakCZ (https://www.github.com/GamakCZ)
   */
  public function reloadWorld(): void {
    $worldName = $this->getWorld()->getFolderName();
    if (!Server::getInstance()->getWorldManager()->isWorldGenerated($worldName)) {
      return;
    }
    if (Server::getInstance()->getWorldManager()->isWorldLoaded($worldName)) {
      Server::getInstance()->getWorldManager()->unloadWorld(Server::getInstance()->getWorldManager()->getWorldByName($worldName));
    }
    $zipPath = Annihilation::getPlugin()->getDataFolder() . "worlds/" . $worldName . ".zip";
    if(!file_exists($zipPath)) {
      return;
    }
    $zipArchive = new \ZipArchive();
    $zipArchive->open($zipPath);
    $zipArchive->extractTo(Server::getInstance()->getDataPath() . "worlds");
    $zipArchive->close();
    Server::getInstance()->getWorldManager()->loadWorld($worldName);
  }
}
