(()=>{"use strict";var e={d:(t,a)=>{for(var r in a)e.o(a,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:a[r]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{fetchServicesAssistantThreadsOpenAi:()=>n});const a=window.aicp.helpers,{QUADLAYERS_AICP_API_ASSINTANT_THREADS_REST_ROUTES:r}=aicpApiAssistantThreads,n=async e=>{try{const t=await(({data:e})=>(0,a.apiFetch)({path:r.assistants_threads,data:e,method:"POST"}))({data:e});return(0,a.handleFrontendApiResponse)(t)?t:null}catch(e){return console.error(e),null}};(window.aicp=window.aicp||{})["api-assistant-threads"]=t})();