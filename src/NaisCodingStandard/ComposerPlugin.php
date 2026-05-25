<?php
declare(strict_types = 1);

namespace NaisCodingStandard;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Plugin for automatic git hook installation
 */
class ComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    private const string HOOK_FILE = '/../bash/pre-commit';

    private const string GIT_DIR = '/../../../../../.git';

    private const string HOOK_DESTINATION = '/../../../../../.git/hooks';

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function activate(Composer $composer, IOInterface $io) : void
    {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents() : array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onPostInstallUpdateCmd',
            ScriptEvents::POST_UPDATE_CMD => 'onPostInstallUpdateCmd',
        ];
    }

    /**
     * Install the pre-commit hook when the project root contains .git.
     */
    public function onPostInstallUpdateCmd(Event $event) : void
    {
        $gitDir = __DIR__ . self::GIT_DIR;
        if (!is_dir($gitDir)) {
            $event->getIO()->writeError(
                'Directory "' . $gitDir . '" not found. Installation of git hook failed'
            );

            return;
        }

        $hookDestination = __DIR__ . self::HOOK_DESTINATION;
        if (!is_dir($hookDestination)) {
            mkdir($hookDestination);
        }

        copy(__DIR__ . self::HOOK_FILE, $hookDestination . '/pre-commit');
        chmod($hookDestination . '/pre-commit', 0775);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function deactivate(Composer $composer, IOInterface $io) : void
    {
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function uninstall(Composer $composer, IOInterface $io) : void
    {
    }
}
