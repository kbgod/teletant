<?php

namespace Askoldex\Teletant\States;


use Askoldex\Teletant\Context;
use Askoldex\Teletant\Exception\StageException;
use Askoldex\Teletant\Middleware\Dispatcher;

class Stage
{

    use Dispatcher;

    /**
     * @var callable
     */
    protected $eventProcessor;

    /**
     * @var Scene[]
     */
    private $scenes = [];

    public function addScene(Scene $scene)
    {
        $this->scenes[$scene->getName()] = $scene;
    }

    /**
     * @param Scene[] ...$scenes
     */
    public function addScenes(...$scenes)
    {
        foreach ($scenes as $scene) {
            $this->addScene($scene);
        }
    }

    /**
     * @param Context $ctx
     * @throws StageException
     */
    public function handleEnter(Context $ctx)
    {
        $this->scene($ctx)->handleEnter($ctx);
    }

    /**
     * @param Context $ctx
     * @throws StageException
     */
    public function handleLeave(Context $ctx)
    {
        $this->scene($ctx)->handleLeave($ctx);
    }

    /**
     * @param Context $ctx
     * @return Scene
     * @throws StageException
     */
    public function scene(Context $ctx): Scene
    {
        $scene = $this->scenes[$ctx->Storage()->getScene()];
        if ($scene instanceof Scene) {
            return $scene;
        } else {
            throw new StageException('Stage "' . $ctx->Storage()->getScene() . '" not found!');
        }
    }

    /**
     * @param Context $ctx
     * @param string $sceneName
     * @throws StageException
     */
    public function enterScene(Context $ctx, string $sceneName)
    {
        $scene = $this->scenes[$sceneName];
        if ($scene instanceof Scene) {
            $ctx->Storage()->setScene($sceneName);
            $scene->handleEnter($ctx);
        } else {
            throw new StageException('Stage "' . $sceneName . '" not found!');
        }
    }

    /**
     * @param Context $ctx
     * @throws StageException
     */
    public function leaveScene(Context $ctx)
    {
        $scene = $this->scene($ctx);
        $scene->handleLeave($ctx);
        $ctx->Storage()->setScene('');
    }

    public function middleware()
    {
        $stage = &$this;
        return function (Context $ctx, $next) use ($stage) {
            if ($ctx->Storage() == null) throw new StageException('Setup Storage in Context for using Stage');
            $ctx->setStage($stage);
            if ($ctx->Storage()->getScene() != '') {
                $this->eventProcessor = function (Context $ctx) {
                    foreach ($ctx->Stage()->scene($ctx)->eventHandler()->getEvents() as $event) {
                        if ($event->invoke($ctx) == true) break;
                    }
                };
                $this->boot()->run($ctx);
                return true;
            }
            return $next($ctx);
        };
    }
}