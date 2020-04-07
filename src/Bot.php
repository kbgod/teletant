<?php

namespace Askoldex\Teletant;


use Askoldex\Teletant\Entities\Update;
use Askoldex\Teletant\Events\EventBuilder;
use Askoldex\Teletant\Exception\TeletantException;
use Askoldex\Teletant\Middleware\Dispatcher;

class Bot
{
    use EventBuilder;
    use Dispatcher;

    private $api;
    private $stage;
    private $ctx;

    /**
     * @var Log
     */
    private $logger;

    /**
     * @var callable
     */
    protected $eventProcessor;

    public function __construct(Settings $settings)
    {
        $this->logger = new Log($settings->getLogger());
        $this->setApi(new Api($settings, $this->logger));

        /* Initialize Traits */
        $this->bootEventBuilder();
    }

    public function logger(): Log
    {
        return $this->logger;
    }

    /**
     * @return Api
     */
    public function Api(): Api
    {
        return $this->api;
    }

    /**
     * @param Api $api
     * @return self
     */
    public function setApi(Api $api): self
    {
        $this->api = $api;
        return $this;
    }

    /**
     * @return Context
     */
    public function Ctx(): Context
    {
        return $this->ctx;
    }

    /**
     * @param Context $ctx
     * @return self
     */
    private function setCtx(Context $ctx): self
    {
        $this->ctx = $ctx;
        return $this;
    }

    private function handleUpdate(Update $update)
    {
        $this->logger->debug('Received update:', $update->export());
        $this->setCtx(new Context($update, $this->Api()));
        $bot = $this;
        $this->eventProcessor = function (Context $ctx) use ($bot) {
            foreach ($bot->eventHandler()->getEvents() as $event) {
                if ($event->invoke($ctx) == true) break;
            }
        };
        $this->boot()->run($this->Ctx());

    }

    /**
     * @throws Exception\TeletantException
     */
    public function polling()
    {
        $this->logger->info('Bot started as long poll');
        $update_id = 0;
        while (true) {
            $updates = $this->Api()->getUpdates(['offset' => $update_id, 'timeout' => 600]);
            foreach ($updates->each() as $update) {
                $update_id = $update->updateId() + 1;
                $this->handleUpdate($update);
            }
        }
    }

    /**
     * @param null $data
     * @throws TeletantException
     */
    public function listen($data = null)
    {
        $data = $data == null ? file_get_contents('php://input') : $data;
        if($data == null)
            throw new TeletantException('Event data is empty');
        $this->handleUpdate(new Update(json_decode($data, true)));
    }
}