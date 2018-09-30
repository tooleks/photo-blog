const state = {
    pageStatusCode: "",
    pageName: "",
    pageDescription: "",
    pageKeywords: "",
    pageTitle: "",
    pageImage: "",
    pageUrl: "",
    pageCanonicalUrl: "",
};

const mutations = {
    setPageStatusCode(state, pageStatusCode) {
        state.pageStatusCode = pageStatusCode;
    },
    setPageName(state, pageName) {
        state.pageName = pageName;
    },
    setPageDescription(state, pageDescription) {
        state.pageDescription = pageDescription;
    },
    setPageKeywords(state, pageKeywords) {
        state.pageKeywords = pageKeywords;
    },
    setPageTitle(state, pageTitle) {
        state.pageTitle = pageTitle;
    },
    setPageImage(state, pageImage) {
        state.pageImage = pageImage;
    },
    setPageUrl(state, pageUrl) {
        state.pageUrl = pageUrl;
    },
    setPageCanonicalUrl(state, pageCanonicalUrl) {
        state.pageCanonicalUrl = pageCanonicalUrl;
    },
};

const actions = {
    setPageStatusCode({commit}, pageStatusCode) {
        commit("setPageStatusCode", pageStatusCode);
    },
    setPageName({commit}, pageName) {
        commit("setPageName", pageName);
    },
    setPageDescription({commit}, pageDescription) {
        commit("setPageDescription", pageDescription);
    },
    setPageKeywords({commit}, pageKeywords) {
        commit("setPageKeywords", pageKeywords);
    },
    setPageTitle({commit}, pageTitle) {
        commit("setPageTitle", pageTitle);
    },
    setPageImage({commit}, pageImage) {
        commit("setPageImage", pageImage);
    },
    setPageUrl({commit}, pageUrl) {
        commit("setPageUrl", pageUrl);
    },
    setPageCanonicalUrl({commit}, pageCanonicalUrl) {
        commit("setPageCanonicalUrl", pageCanonicalUrl);
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
