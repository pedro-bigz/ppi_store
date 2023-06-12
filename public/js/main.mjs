import * as anuncios from './anuncios/index.mjs';
import * as auth from './auth/index.mjs';
import { pageResolver } from './helpers/page-resolver.mjs';

pageResolver({ auth, anuncios }).load();
