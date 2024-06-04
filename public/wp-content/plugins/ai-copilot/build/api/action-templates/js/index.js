(()=>{"use strict";var e={d:(t,s)=>{for(var c in s)e.o(s,c)&&!e.o(t,c)&&Object.defineProperty(t,c,{enumerable:!0,get:s[c]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{INITIAL_STATE:()=>r,QUADLAYERS_AICP_STORE_NAME:()=>w,STORE_NAME:()=>n,actions:()=>c,fetchRestApiActions:()=>h,reducer:()=>i,resolvers:()=>o,selectors:()=>s,useApiActionTemplates:()=>_});var s={};e.r(s),e.d(s,{getActions:()=>d});var c={};e.r(c),e.d(c,{createAction:()=>g,deleteAction:()=>S,editAction:()=>T,setActions:()=>y});var o={};e.r(o),e.d(o,{getActions:()=>m});var i={};e.r(i),e.d(i,{default:()=>E});const a=window.wp.data,n="aicp/actions/store",r={actions:[]},d=e=>e.actions,p=window.wp.i18n,l=window.wp.notices,A=window.aicp.helpers,{QUADLAYERS_AICP_API_ACTION_TEMPLATES_REST_ROUTES:u}=aicpApiActionTemplates,h=({method:e,data:t}={})=>(0,A.apiFetch)({path:u.actions,method:e,data:t});function _(){const{createAction:e,editAction:t,deleteAction:s}=(0,a.useDispatch)(n),{actions:c,isResolvingActions:o,hasResolvedActions:i}=(0,a.useSelect)((e=>{const{isResolving:t,hasFinishedResolution:s,getActions:c}=e(n);let o=e("core/editor").getEditedPostAttribute("type");if(!o){const e=document.getElementById("post_type");e&&(o=e.value)}return{actions:[...([...A.SYSTEM_ACTIONS_TEMPLATES,...c()||[]].filter((e=>e.action_post_type.includes("all")||e.action_post_type.includes(o)||!o))||[]).map((e=>"action_origin"in e?e:{...e,action_origin:"user"}))],isResolvingActions:t("getActions"),hasResolvedActions:s("getActions")}}),[]);return{actions:c,isResolvingActions:o,hasResolvedActions:i,hasActions:!(!i||!c?.length),createAction:e,editAction:t,deleteAction:s}}const y=e=>({type:"SET_ACTIONS",payload:e}),g=e=>async({registry:t,dispatch:s,select:c})=>{const o=c.getActions(),i=await h({method:"POST",data:e});return i?.code||i?.message?(t.dispatch(l.store).createSuccessNotice((0,p.sprintf)("%s: %s",i.code,i.message),{type:"snackbar"}),!1):(o.push(i),s.setActions([...o]),t.dispatch(l.store).createSuccessNotice((0,p.__)("The action has been created successfully.","ai-copilot"),{type:"snackbar"}),i.action_id)},S=e=>async({registry:t,dispatch:s,select:c})=>{const o=c.getActions(),i=await h({method:"DELETE",data:{action_id:e}});if(i?.code||i?.message)return t.dispatch(l.store).createSuccessNotice((0,p.sprintf)("%s: %s",i.code,i.message),{type:"snackbar"}),!1;const a=o.filter((t=>parseInt(t.action_id)!==parseInt(e)));return s.setActions([...a]),t.dispatch(l.store).createSuccessNotice((0,p.sprintf)((0,p.__)("The action %s has been deleted.","ai-copilot"),e),{type:"snackbar"}),!0},T=e=>async({registry:t,dispatch:s,select:c})=>{const o=c.getActions(),i=await h({method:"PATCH",data:e});return i?.code||i?.message?(t.dispatch(l.store).createSuccessNotice((0,p.sprintf)("%s: %s",i.code,i.message),{type:"snackbar"}),!1):(s.setActions([...o.map((t=>t.action_id==e.action_id?e:t))]),t.dispatch(l.store).createSuccessNotice((0,p.__)("The action has been updated successfully.","ai-copilot"),{type:"snackbar"}),!0)},m=async()=>{try{const e=await h({method:"GET"});return y(e)}catch(e){console.error(e)}};function E(e=r,t){return"SET_ACTIONS"===t.type?{...e,actions:t.payload}:e}const f=(0,a.createReduxStore)(n,{reducer:E,actions:c,selectors:s,resolvers:o});(0,a.register)(f);const w=n;(window.aicp=window.aicp||{})["api-action-templates"]=t})();