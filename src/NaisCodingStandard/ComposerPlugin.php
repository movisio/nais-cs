<?php
declare(strict_types = 1);

namespace NaisCodingStandard;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Plugin for automatic git hook installation
 */
class ComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    private const HOOK_FILE = '/../bash/pre-commit';
    private const GIT_DIR = '/../../../../../.git';
    private const HOOK_DESTINATION = '/../../../../../.git/hooks';

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io) : void
    {
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onPostInstallUpdateCmd',
            ScriptEvents::POST_UPDATE_CMD => 'onPostInstallUpdateCmd',
        ];
    }

    /**
     * Install the hook
     * @param Event $event
     */
    public function onPostInstallUpdateCmd(Event $event) : void
    {
        if (!is_dir(__DIR__ . self::GIT_DIR)) {
            $event->getIO()->writeError('Directory "' . __DIR__ . self::GIT_DIR . '" not found. Installation of git hook failed');
            return;
        }
        if (!is_dir(__DIR__ . self::HOOK_DESTINATION)) {
            mkdir(__DIR__ . self::HOOK_DESTINATION);
        }
        copy(__DIR__ . self::HOOK_FILE, __DIR__ . self::HOOK_DESTINATION . '/pre-commit');
        chmod(__DIR__ . self::HOOK_DESTINATION . '/pre-commit', 0775);
    }
}
