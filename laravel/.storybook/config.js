import { configure } from '@storybook/react';

// automatically import all files ending in *.stories.js
configure(require.context('../resources/js', true, /\.stories\.js$/), module);
