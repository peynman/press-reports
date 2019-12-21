
import LPDBrowse from './dashboard/Pages/LpdBrowse'
import LpdCreate from './dashboard/Pages/LpdCreate'
import LpdUpdate from './dashboard/Pages/LpdUpdate'
import LpdWidgets from './dashboard/Pages/LpdWidgets'
import LpdVerb from './dashboard/Pages/LpdVerb'

export default [
    { path: '/', component: LpdWidgets },
    { path: '/:resource', component: LPDBrowse },
    { path: '/:resource/create', component: LpdCreate },
    { path: '/:resource/widgets', component: LpdWidgets },
    { path: '/:resource/edit/:id', component: LpdUpdate },
    { path: '/:resource/:verb/*', component: LpdVerb },
]
