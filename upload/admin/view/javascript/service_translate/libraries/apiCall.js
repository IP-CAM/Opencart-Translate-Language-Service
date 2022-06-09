import store  from '../store.vue.js';

export const missing_translations = (parameters) => {
  parameters.route = 'api/st/products';
  parameters.user_token = get_token();
  return execute(parameters)
  .then((result) => {
    let missing = result;
    if(missing === '')
      missing = 0;
    return missing;
  });
};

export const traslate = (parameters) => {
  parameters.route = 'api/st/translate';
  parameters.translation_service = store.getters.getSetting('st_service');    
  parameters.meta_title_suffix = store.getters.getSetting('st_suffix');
  parameters.meta_from_title = store.getters.getSetting('st_meta_fromt_tile');
  parameters.translate_description = store.getters.getSetting('st_translate_description');
  return execute(parameters)
  .then((result) => {
    return result;
  });
};

export const source_lang = (parameters) => {
  parameters.route = 'api/st/languages/source_for_lang';  
  parameters.user_token = get_token();
  return execute(parameters)
  .then((result) => {
    return result;
  });
};

const execute = (parameters) => {
  return axios.get(get_url(),{
    params: parameters
  })
  .then((result) => {
    return result.data;        
  })
  .catch(error => {
    throw 'No data';
  })
};

const get_url = () => store.getters.getBasicUrlData.site_url + 'index.php';
const get_token = () => store.getters.getBasicUrlData.token;