const adminMenu = [

  {
    section: '',

    items: [

      { name: 'Dashboard', path: '/dashboard' }
    ]
  },

  {
    section: 'Management',

    items: [

      { name: 'Users', path: '/users' },

      { name: 'Meters', path: '/meters' },

      { name: 'Buildings', path: '/buildings' }
    ]
  },

  {
    section: 'Operations',

    items: [

      { name: 'Reading Queue', path: '/reading-queue' },

      { name: 'Anomalies', path: '/anomalies' }
    ]
  },

  {
    section: 'Insights',

    items: [

      { name: 'Analytics', path: '/analytics' },

      { name: 'Export Reports', path: '/reports' }
    ]
  }
]

export const menu = {

  super_admin: [

    ...adminMenu,

    {
      section: 'Calendar',

      items: [

        { name: 'Events', path: '/events' }
      ]
    }
  ],

  campus_admin: [

    ...adminMenu,

    {
      section: 'Calendar',

      items: [

        { name: 'Events', path: '/events' }
      ]
    }
  ],

  staff: [

    ...adminMenu
  ],

  calendar_admin: [

    {
      section: '',

      items: [

        { name: 'Dashboard', path: '/dashboard' }
      ]
    },

    {
      section: 'Calendar',

      items: [

        { name: 'Events', path: '/events' },

        { name: 'Calendar', path: '/calendar' }
      ]
    }
  ]
}