import './bootstrap';

import Alpine from 'alpinejs';
import { registerCareRoomsPage } from './care-rooms-search';
import { registerGeographyPage } from './geography-search';
import { registerOperatingBlocksPage } from './operating-blocks-search';
import { registerReceptionRoomsPage } from './reception-rooms-search';
import { registerServicesPage } from './services-search';
import { registerSpecialtiesPage } from './specialties-search';
import { registerAssistantChat } from './assistant-chat';

window.Alpine = Alpine;

registerGeographyPage();
registerServicesPage();
registerSpecialtiesPage();
registerOperatingBlocksPage();
registerCareRoomsPage();
registerReceptionRoomsPage();
registerAssistantChat();

Alpine.start();
