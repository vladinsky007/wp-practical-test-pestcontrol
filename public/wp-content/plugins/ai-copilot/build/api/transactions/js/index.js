(()=>{"use strict";var t={d:(s,a)=>{for(var n in a)t.o(a,n)&&!t.o(s,n)&&Object.defineProperty(s,n,{enumerable:!0,get:a[n]})},o:(t,s)=>Object.prototype.hasOwnProperty.call(t,s),r:t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}},s={};t.r(s),t.d(s,{INITIAL_STATE:()=>c,QUADLAYERS_AICP_STORE_NAME:()=>R,STORE_NAME:()=>r,actions:()=>n,fetchRestApiTransactions:()=>w,getTransactionCost:()=>_,reducer:()=>o,resolvers:()=>e,selectors:()=>a,useTransactionsApi:()=>y});var a={};t.r(a),t.d(a,{getTransactions:()=>p});var n={};t.r(n),t.d(n,{setTransactions:()=>S,updateTransactions:()=>h});var e={};t.r(e),t.d(e,{getTransactions:()=>m});var o={};t.r(o),t.d(o,{default:()=>v});const i=window.wp.data,r="aicp/stats/store",c={transactions:[]},p=t=>t.transactions,d=window.wp.i18n,u=window.wp.notices,l=window.aicp.helpers,{QUADLAYERS_AICP_API_TRANSACTIONS_REST_ROUTES:T}=(window.lodash,window.aicp["api-admin-menu"],window.wp.element,aicpApiTransactions),_=async(t,s={input:0,ouput:0},a={input:0,ouput:0})=>{const n=a?.input||0,e=a?.ouput||0,o=n+e,i=s?.input||0,r=s?.ouput||0,c=i+r,p={consumer_module:t.consumer_module,api_type:t.api_type,api_service:t.api_service,api_service_model:t.api_service_model,tokens_qty_input:n,tokens_qty_output:e,tokens_qty_total:o,transaction_cost_input:i,transaction_cost_output:r,transaction_cost_total:c},d=await w({method:"POST",data:{transaction:p}});return(0,l.handleApiResponse)(d)?p:null},w=({method:t,data:s,params:a}={})=>(0,l.apiFetch)({path:`${T.transactions}${a?.length>0?a:""}`,method:t,data:s});function y({defaultParams:t}){const{transactions:s,isResolvingTransactions:a,hasResolvedTransactions:n}=(0,i.useSelect)((s=>{if(!t)return{transactions:[],isResolvingTransactions:!1,hasResolvedTransactions:!1};const{getTransactions:a,isResolving:n,hasFinishedResolution:e}=s(r);return{transactions:a(t),isResolvingTransactions:n("getTransactions"),hasResolvedTransactions:e("getTransactions")}}),[]),{updateTransactions:e}=(0,i.useDispatch)(r);return{updateTransactions:e,transactions:s,isResolvingTransactions:a,hasResolvedTransactions:n,hasTransactions:!(!n||0===s.length)}}const S=async t=>({type:"SET_TRANSACTIONS",payload:t}),h=t=>async({registry:s,dispatch:a,select:n})=>{const e=await w({method:"GET",params:(0,l.buildParams)(t)});return e?.code||e?.message?(s.dispatch(u.store).createSuccessNotice((0,d.sprintf)("%s: %s",e.code,e.message),{type:"snackbar"}),!1):(a.setTransactions([...e]),e)},m=async t=>h(t);function v(t=c,s){return"SET_TRANSACTIONS"===s.type?{...t,transactions:s.payload}:t}const g=(0,i.createReduxStore)(r,{reducer:v,actions:n,selectors:a,resolvers:e});(0,i.register)(g);const R=r;(window.aicp=window.aicp||{})["api-transactions"]=s})();