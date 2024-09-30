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
