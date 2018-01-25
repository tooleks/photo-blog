export default {
    app: {
        name: process.env.MIX_APP_NAME,
        description: process.env.MIX_APP_DESCRIPTION,
        keywords: process.env.MIX_APP_KEYWORDS,
        author: process.env.MIX_APP_AUTHOR,
    },
    url: {
        app: process.env.MIX_URL_APP,
        image: process.env.MIX_URL_IMAGE,
        api: process.env.MIX_URL_API,
        social: {
            facebook: process.env.MIX_URL_SOCIAL_FACEBOOK,
            github: process.env.MIX_URL_SOCIAL_GITHUB,
        },
    },
    credentials: {
        googleAnalytics: {
            trackingId: process.env.MIX_CREDENTIALS_GOOGLE_ANALYTICS_TRACKING_ID,
        },
        googleReCaptcha: {
            siteKey: process.env.MIX_CREDENTIALS_GOOGLE_RECAPTCHA_SITE_KEY,
        },
    },
}
