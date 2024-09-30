type SigninPayload = {
  email: string;
  password: string;
};

export interface SignInSuccess {
  token: string;
  user: object;
}

export interface ApiResponse<TData = any> {
  message: string;
  data: TData;
  errors: object;
  status: number;
}

export interface GetTaskResponse {
  tasks: Array;
  task_statuses: Array;
  task_priorities: Array;
}

export interface CreateTaskPayload {
  name: string;
  label: string;
  priority: string;
  note: string;
  status: string;
}

export interface TaskRowData {
  name: string;
  label: string;
  priority: string;
  status: string;
}
