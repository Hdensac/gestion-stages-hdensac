// fontawesome.js
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { 
    faUsers, 
    faBuilding, 
    faUserGraduate, 
    faBriefcase, 
    faUserPlus,
    faFileAlt,
    faChartBar,
    faComments,
    faCog,
    faExclamationCircle,
    faDownload, // Ajout de l'icône download
    faFileSignature // ajout de l'icône file-signature
} from '@fortawesome/free-solid-svg-icons';

// Add icons to the library
library.add(
    faUsers, 
    faBuilding, 
    faUserGraduate, 
    faBriefcase, 
    faUserPlus,
    faFileAlt,
    faChartBar,
    faComments,
    faCog,
    faExclamationCircle,
    faDownload, // Ajout de l'icône download à la librairie
    faFileSignature // ajout à la librairie
);

export { FontAwesomeIcon };