import './bootstrap';

import Alpine from 'alpinejs';
import { registerGeographyPage } from './geography-search';
import { registerServicesPage } from './services-search';
import { registerSpecialtiesPage } from './specialties-search';
import { registerAssistantChat } from './assistant-chat';

window.Alpine = Alpine;

registerGeographyPage();
registerServicesPage();
registerSpecialtiesPage();
registerAssistantChat();

Alpine.start();
