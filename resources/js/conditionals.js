import IndexConditionalField from './components/conditional/IndexField'
import DetailConditionalField from './components/conditional/DetailField'
import FormConditionalField from './components/conditional/FormField'
import IndexConditionalManyField from './components/conditional-many/IndexField';
import DetailConditionalManyField from './components/conditional-many/DetailField';

Nova.booting((app, store) => {
    app.component('index-conditional', IndexConditionalField)
    app.component('detail-conditional', DetailConditionalField)
    app.component('form-conditional', FormConditionalField)
    app.component('index-conditional-many', IndexConditionalManyField);
    app.component('detail-conditional-many', DetailConditionalManyField);
});