export interface TopFacility {

  facility_id: number

  total: number

  facility: {

    id: number

    name: string
  } | null
}

export interface UpcomingEvent {

  id: number

  event_name: string

  event_date: string

  status: string

  facility: {

    id: number

    name: string
  } | null
}

export interface TodayEvent {

  id: number

  organization: string

  event_name: string

  start_time: string

  end_time: string

  status: string

  facility: {

    id: number

    name: string
  } | null
}

export interface CalendarDashboard {

  reservations_today: number

  reservations_week: number

  reservations_month: number

  upcoming: number

  ongoing: number

  completed: number

  cancelled: number

  rescheduled: number

  top_facilities: TopFacility[]

  upcoming_events: UpcomingEvent[]

  today_events: TodayEvent[]
}