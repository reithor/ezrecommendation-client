<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzRecommendationClient\eZ\Publish\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class PublishVersion extends PersistenceAwareBase
{
    /**
     * {@inheritdoc}
     */
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\PublishVersionSignal) {
            return;
        }

        $relations = $this->persistenceHandler
            ->contentHandler()
            ->loadReverseRelations($signal->contentId);

        $this->signalSlotService->updateContent((int) $signal->contentId, (int) $signal->versionNo);

        foreach ($relations as $relation) {
            $this->signalSlotService->updateContent((int) $relation->destinationContentId);
        }
    }
}
