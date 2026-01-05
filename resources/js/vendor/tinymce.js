/* Import TinyMCE */
import tinymce from 'tinymce';

/* Default icons are required. After that, import custom icons if applicable */
import 'tinymce/icons/default/icons.min.js';

/* Required TinyMCE components */
import 'tinymce/themes/silver/theme.min.js';
import 'tinymce/models/dom/model.min.js';

/* Import the default skin (oxide). Replace with a custom skin if required. */
import 'tinymce/skins/ui/oxide/skin.js';
import 'tinymce/skins/ui/oxide-dark/skin.js';

/* Import plugins */
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/image';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/quickbars';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/help/js/i18n/keynav/en';

// Import premium plugins from NPM
import 'tinymce-premium/plugins/advcode';
import 'tinymce-premium/plugins/tinycomments';
// Always include the licensekeymanager plugin when using premium plugins with a commercial license.
import 'tinymce-premium/plugins/licensekeymanager';

/* content UI CSS is required (using the default oxide skin) */
import 'tinymce/skins/ui/oxide/content.js';
import 'tinymce/skins/ui/oxide-dark/content.js';

/* The default content CSS can be changed or replaced with appropriate CSS for the editor content. */
import 'tinymce/skins/content/default/content.js';

window.tinymce = tinymce;
